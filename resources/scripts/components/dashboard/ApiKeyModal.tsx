import React, { useContext } from 'react';
import { Button } from '@carbon/react';
import asModal from '@/hoc/asModal';
import ModalContext from '@/context/ModalContext';
import CopyOnClick from '@/components/elements/CopyOnClick';

interface Props {
    apiKey: string;
}

const ApiKeyModal = ({ apiKey }: Props) => {
    const { dismiss } = useContext(ModalContext);

    return (
        <>
            <h3>Your API Key</h3>
            <p className={'ptero-muted'}>
                The API key you have requested is shown below. Please store this in a safe location, it will not be
                shown again.
            </p>
            <pre className={'ptero-code'} style={{ overflowX: 'auto' }}>
                <CopyOnClick text={apiKey}>
                    <code>{apiKey}</code>
                </CopyOnClick>
            </pre>
            <div className={'ptero-modal-actions'}>
                <Button type={'button'} onClick={() => dismiss()}>
                    Close
                </Button>
            </div>
        </>
    );
};

ApiKeyModal.displayName = 'ApiKeyModal';

export default asModal<Props>({
    closeOnEscape: false,
    closeOnBackground: false,
})(ApiKeyModal);
