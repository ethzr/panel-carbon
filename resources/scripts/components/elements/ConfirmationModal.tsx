import React, { useContext } from 'react';
import Button from '@/components/elements/Button';
import asModal from '@/hoc/asModal';
import ModalContext from '@/context/ModalContext';

type Props = {
    title: string;
    buttonText: string;
    onConfirmed: () => void;
    showSpinnerOverlay?: boolean;
};

const ConfirmationModal: React.FC<Props> = ({ title, children, buttonText, onConfirmed }) => {
    const { dismiss } = useContext(ModalContext);

    return (
        <>
            <h2 className={'cds--productive-heading-03'} style={{ marginBottom: '1.5rem' }}>
                {title}
            </h2>
            <div className={'cds--body-compact-01'}>{children}</div>
            <div className={'ptero-modal-actions'}>
                <Button isSecondary onClick={() => dismiss()}>
                    Cancel
                </Button>
                <Button color={'red'} onClick={() => onConfirmed()}>
                    {buttonText}
                </Button>
            </div>
        </>
    );
};

ConfirmationModal.displayName = 'ConfirmationModal';

export default asModal<Props>((props) => ({
    showSpinnerOverlay: props.showSpinnerOverlay,
}))(ConfirmationModal);
