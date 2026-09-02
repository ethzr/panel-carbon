import React, { useEffect, useState } from 'react';
import { ServerContext } from '@/state/server';
import Modal from '@/components/elements/Modal';
import { Button } from '@carbon/react';
import FlashMessageRender from '@/components/FlashMessageRender';
import useFlash from '@/plugins/useFlash';
import { SocketEvent } from '@/components/server/events';
import { useStoreState } from 'easy-peasy';

const SteamDiskSpaceFeature = () => {
    const [visible, setVisible] = useState(false);
    const [loading] = useState(false);

    const status = ServerContext.useStoreState((state) => state.status.value);
    const { clearFlashes } = useFlash();
    const { connected, instance } = ServerContext.useStoreState((state) => state.socket);
    const isAdmin = useStoreState((state) => state.user.data!.rootAdmin);

    useEffect(() => {
        if (!connected || !instance || status === 'running') return;

        const errors = ['steamcmd needs 250mb of free disk space to update', '0x202 after update job'];

        const listener = (line: string) => {
            if (errors.some((p) => line.toLowerCase().includes(p))) {
                setVisible(true);
            }
        };

        instance.addListener(SocketEvent.CONSOLE_OUTPUT, listener);

        return () => {
            instance.removeListener(SocketEvent.CONSOLE_OUTPUT, listener);
        };
    }, [connected, instance, status]);

    useEffect(() => {
        clearFlashes('feature:steamDiskSpace');
    }, []);

    return (
        <Modal
            visible={visible}
            onDismissed={() => setVisible(false)}
            closeOnBackground={false}
            showSpinnerOverlay={loading}
        >
            <FlashMessageRender key={'feature:steamDiskSpace'} />
            {isAdmin ? (
                <>
                    <h2>Out of available disk space...</h2>
                    <p>
                        This server has run out of available disk space and cannot complete the install or update
                        process.
                    </p>
                    <p>
                        Ensure the machine has enough disk space by typing <code className={'ptero-code'}>df -h</code>{' '}
                        on the machine hosting this server. Delete files or increase the available disk space to resolve
                        the issue.
                    </p>
                    <div className={'ptero-modal-actions'}>
                        <Button onClick={() => setVisible(false)}>Close</Button>
                    </div>
                </>
            ) : (
                <>
                    <h2>Out of available disk space...</h2>
                    <p>
                        This server has run out of available disk space and cannot complete the install or update
                        process. Please get in touch with the administrator(s) and inform them of disk space issues.
                    </p>
                    <div className={'ptero-modal-actions'}>
                        <Button onClick={() => setVisible(false)}>Close</Button>
                    </div>
                </>
            )}
        </Modal>
    );
};

export default SteamDiskSpaceFeature;
