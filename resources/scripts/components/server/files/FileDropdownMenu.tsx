import React, { memo, useRef, useState } from 'react';
import RenameFileModal from '@/components/server/files/RenameFileModal';
import { ServerContext } from '@/state/server';
import { join } from 'pathe';
import deleteFiles from '@/api/server/files/deleteFiles';
import SpinnerOverlay from '@/components/elements/SpinnerOverlay';
import copyFile from '@/api/server/files/copyFile';
import getFileDownloadUrl from '@/api/server/files/getFileDownloadUrl';
import useFlash from '@/plugins/useFlash';
import { FileObject } from '@/api/server/files/loadDirectory';
import useFileManagerSwr from '@/plugins/useFileManagerSwr';
import useEventListener from '@/plugins/useEventListener';
import compressFiles from '@/api/server/files/compressFiles';
import decompressFiles from '@/api/server/files/decompressFiles';
import isEqual from 'react-fast-compare';
import ChmodFileModal from '@/components/server/files/ChmodFileModal';
import { Dialog } from '@/components/elements/dialog';
import { OverflowMenu, OverflowMenuItem } from '@carbon/react';
import { usePermissions } from '@/plugins/usePermissions';

type ModalType = 'rename' | 'move' | 'chmod';

const FileDropdownMenu = ({ file }: { file: FileObject }) => {
    const buttonHost = useRef<HTMLDivElement>(null);
    const [showSpinner, setShowSpinner] = useState(false);
    const [modal, setModal] = useState<ModalType | null>(null);
    const [showConfirmation, setShowConfirmation] = useState(false);

    const uuid = ServerContext.useStoreState((state) => state.server.data!.uuid);
    const { mutate } = useFileManagerSwr();
    const { clearAndAddHttpError, clearFlashes } = useFlash();
    const directory = ServerContext.useStoreState((state) => state.files.directory);
    const [canUpdate] = usePermissions(['file.update']);
    const [canCreate] = usePermissions(['file.create']);
    const [canArchive] = usePermissions(['file.archive']);
    const [canDelete] = usePermissions(['file.delete']);

    useEventListener(`pterodactyl:files:ctx:${file.key}`, () => {
        buttonHost.current?.querySelector('button')?.click();
    });

    const doDeletion = () => {
        clearFlashes('files');
        mutate((files) => files.filter((f) => f.key !== file.key), false);
        deleteFiles(uuid, directory, [file.name]).catch((error) => {
            mutate();
            clearAndAddHttpError({ key: 'files', error });
        });
    };

    const doCopy = () => {
        setShowSpinner(true);
        clearFlashes('files');
        copyFile(uuid, join(directory, file.name))
            .then(() => mutate())
            .catch((error) => clearAndAddHttpError({ key: 'files', error }))
            .then(() => setShowSpinner(false));
    };

    const doDownload = () => {
        setShowSpinner(true);
        clearFlashes('files');
        getFileDownloadUrl(uuid, join(directory, file.name))
            .then((url) => {
                // @ts-expect-error this is valid
                window.location = url;
            })
            .catch((error) => clearAndAddHttpError({ key: 'files', error }))
            .then(() => setShowSpinner(false));
    };

    const doArchive = () => {
        setShowSpinner(true);
        clearFlashes('files');
        compressFiles(uuid, directory, [file.name])
            .then(() => mutate())
            .catch((error) => clearAndAddHttpError({ key: 'files', error }))
            .then(() => setShowSpinner(false));
    };

    const doUnarchive = () => {
        setShowSpinner(true);
        clearFlashes('files');
        decompressFiles(uuid, directory, file.name)
            .then(() => mutate())
            .catch((error) => clearAndAddHttpError({ key: 'files', error }))
            .then(() => setShowSpinner(false));
    };

    return (
        <div ref={buttonHost}>
            <Dialog.Confirm
                open={showConfirmation}
                onClose={() => setShowConfirmation(false)}
                title={`Delete ${file.isFile ? 'File' : 'Directory'}`}
                confirm={'Delete'}
                onConfirmed={doDeletion}
            >
                You will not be able to recover the contents of&nbsp;
                <strong>{file.name}</strong> once deleted.
            </Dialog.Confirm>
            {modal === 'chmod' && (
                <ChmodFileModal
                    visible
                    appear
                    files={[{ file: file.name, mode: file.modeBits }]}
                    onDismissed={() => setModal(null)}
                />
            )}
            {(modal === 'rename' || modal === 'move') && (
                <RenameFileModal
                    visible
                    appear
                    files={[file.name]}
                    useMoveTerminology={modal === 'move'}
                    onDismissed={() => setModal(null)}
                />
            )}
            <SpinnerOverlay visible={showSpinner} fixed size={'large'} />
            <OverflowMenu flipped size={'sm'} iconDescription={'File actions'} aria-label={'File actions'}>
                {canUpdate && <OverflowMenuItem itemText={'Rename'} onClick={() => setModal('rename')} />}
                {canUpdate && <OverflowMenuItem itemText={'Move'} onClick={() => setModal('move')} />}
                {canUpdate && <OverflowMenuItem itemText={'Permissions'} onClick={() => setModal('chmod')} />}
                {file.isFile && canCreate && <OverflowMenuItem itemText={'Copy'} onClick={doCopy} />}
                {file.isArchiveType()
                    ? canCreate && <OverflowMenuItem itemText={'Unarchive'} onClick={doUnarchive} />
                    : canArchive && <OverflowMenuItem itemText={'Archive'} onClick={doArchive} />}
                {file.isFile && <OverflowMenuItem itemText={'Download'} onClick={doDownload} />}
                {canDelete && (
                    <OverflowMenuItem hasDivider isDelete itemText={'Delete'} onClick={() => setShowConfirmation(true)} />
                )}
            </OverflowMenu>
        </div>
    );
};

export default memo(FileDropdownMenu, isEqual);
