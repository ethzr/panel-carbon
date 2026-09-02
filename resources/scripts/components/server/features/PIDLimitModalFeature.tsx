import React, { useEffect, useState } from 'react';
import { ServerContext } from '@/state/server';
import Modal from '@/components/elements/Modal';
import { Button } from '@carbon/react';
import FlashMessageRender from '@/components/FlashMessageRender';
import useFlash from '@/plugins/useFlash';
import { SocketEvent } from '@/components/server/events';
import { useStoreState } from 'easy-peasy';
import { FontAwesomeIcon } from '@fortawesome/react-fontawesome';
import { faExclamationTriangle } from '@fortawesome/free-solid-svg-icons';

const PIDLimitModalFeature = () => {
    const [visible, setVisible] = useState(false);
    const [loading] = useState(false);

    const status = ServerContext.useStoreState((state) => state.status.value);
    const { clearFlashes } = useFlash();
    const { connected, instance } = ServerContext.useStoreState((state) => state.socket);
    const isAdmin = useStoreState((state) => state.user.data!.rootAdmin);

    useEffect(() => {
        if (!connected || !instance || status === 'running') return;

        const errors = [
            'pthread_create failed',
            'failed to create thread',
            'unable to create thread',
            'unable to create native thread',
            'unable to create new native thread',
            'exception in thread "craft async scheduler management thread"',
        ];

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
        clearFlashes('feature:pidLimit');
    }, []);

    return (
        <Modal
            visible={visible}
            onDismissed={() => setVisible(false)}
            closeOnBackground={false}
            showSpinnerOverlay={loading}
        >
            <FlashMessageRender key={'feature:pidLimit'} />
            {isAdmin ? (
                <>
                    <div className={'ptero-stack ptero-stack--row'}>
                        <FontAwesomeIcon icon={faExclamationTriangle} color={'orange'} size={'4x'} />
                        <h2>Memory or process limit reached...</h2>
                    </div>
                    <p>This server has reached the maximum process or memory limit.</p>
                    <p>
                        Increasing <code className={'ptero-code'}>container_pid_limit</code> in the wings configuration,{' '}
                        <code className={'ptero-code'}>config.yml</code>, might help resolve this issue.
                    </p>
                    <p>
                        <b>Note: Wings must be restarted for the configuration file changes to take effect</b>
                    </p>
                    <div className={'ptero-modal-actions'}>
                        <Button onClick={() => setVisible(false)}>Close</Button>
                    </div>
                </>
            ) : (
                <>
                    <div className={'ptero-stack ptero-stack--row'}>
                        <FontAwesomeIcon icon={faExclamationTriangle} color={'orange'} size={'4x'} />
                        <h2>Possible resource limit reached...</h2>
                    </div>
                    <p>
                        This server is attempting to use more resources than allocated. Please contact the administrator
                        and give them the error below.
                    </p>
                    <p>
                        <code className={'ptero-code'}>
                            pthread_create failed, Possibly out of memory or process/resource limits reached
                        </code>
                    </p>
                    <div className={'ptero-modal-actions'}>
                        <Button onClick={() => setVisible(false)}>Close</Button>
                    </div>
                </>
            )}
        </Modal>
    );
};

export default PIDLimitModalFeature;
