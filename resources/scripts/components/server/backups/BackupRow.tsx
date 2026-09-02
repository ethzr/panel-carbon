import React from 'react';
import { FontAwesomeIcon } from '@fortawesome/react-fontawesome';
import { faArchive, faLock } from '@fortawesome/free-solid-svg-icons';
import { format, formatDistanceToNow } from 'date-fns';
import Spinner from '@/components/elements/Spinner';
import { bytesToString } from '@/lib/formatters';
import Can from '@/components/elements/Can';
import useWebsocketEvent from '@/plugins/useWebsocketEvent';
import BackupContextMenu from '@/components/server/backups/BackupContextMenu';
import GreyRowBox from '@/components/elements/GreyRowBox';
import getServerBackups from '@/api/swr/getServerBackups';
import { ServerBackup } from '@/api/server/types';
import { SocketEvent } from '@/components/server/events';

interface Props {
    backup: ServerBackup;
    className?: string;
}

export default ({ backup, className }: Props) => {
    const { mutate } = getServerBackups();

    useWebsocketEvent(`${SocketEvent.BACKUP_COMPLETED}:${backup.uuid}` as SocketEvent, (data) => {
        try {
            const parsed = JSON.parse(data);

            mutate(
                (data) => ({
                    ...data,
                    items: data.items.map((b) =>
                        b.uuid !== backup.uuid
                            ? b
                            : {
                                  ...b,
                                  isSuccessful: parsed.is_successful || true,
                                  checksum: (parsed.checksum_type || '') + ':' + (parsed.checksum || ''),
                                  bytes: parsed.file_size || 0,
                                  completedAt: new Date(),
                              }
                    ),
                }),
                false
            );
        } catch (e) {
            console.warn(e);
        }
    });

    return (
        <GreyRowBox className={className}>
            <div className={'ptero-resource-row__icon'}>
                {backup.completedAt !== null ? (
                    backup.isLocked ? (
                        <FontAwesomeIcon icon={faLock} />
                    ) : (
                        <FontAwesomeIcon icon={faArchive} />
                    )
                ) : (
                    <Spinner size={'small'} />
                )}
            </div>
            <div className={'ptero-resource-row__body'}>
                <p>
                    {backup.completedAt !== null && !backup.isSuccessful && (
                        <span className={'cds--tag cds--tag--red cds--tag--sm'} style={{ marginRight: '0.5rem' }}>
                            Failed
                        </span>
                    )}
                    {backup.name}
                    {backup.completedAt !== null && backup.isSuccessful && (
                        <span className={'ptero-muted'} style={{ marginLeft: '0.5rem' }}>
                            {bytesToString(backup.bytes)}
                        </span>
                    )}
                </p>
                <p className={'ptero-code'} style={{ display: 'inline-block', marginTop: '0.25rem' }}>
                    {backup.checksum}
                </p>
            </div>
            <div className={'ptero-resource-row__meta'}>
                <p title={format(backup.createdAt, 'ddd, MMMM do, yyyy HH:mm:ss')}>
                    {formatDistanceToNow(backup.createdAt, { includeSeconds: true, addSuffix: true })}
                </p>
                <span className={'ptero-resource-row__label'}>Created</span>
            </div>
            <Can action={['backup.download', 'backup.restore', 'backup.delete']} matchAny>
                <div className={'ptero-resource-row__actions'}>
                    {backup.completedAt ? <BackupContextMenu backup={backup} /> : null}
                </div>
            </Can>
        </GreyRowBox>
    );
};
