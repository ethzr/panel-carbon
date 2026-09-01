import React, { useEffect, useMemo, useRef, useState } from 'react';
import { ComposedModal, Loading, ModalBody, ModalHeader } from '@carbon/react';
import Fade from '@/components/elements/Fade';

export interface RequiredModalProps {
    visible: boolean;
    onDismissed: () => void;
    appear?: boolean;
    top?: boolean;
}

export interface ModalProps extends RequiredModalProps {
    dismissable?: boolean;
    closeOnEscape?: boolean;
    closeOnBackground?: boolean;
    showSpinnerOverlay?: boolean;
}

export const ModalMask = ({ children, ...props }: React.HTMLAttributes<HTMLDivElement>) => <div {...props}>{children}</div>;

const Modal: React.FC<ModalProps> = ({
    visible,
    dismissable,
    showSpinnerOverlay,
    closeOnBackground = true,
    closeOnEscape = true,
    onDismissed,
    children,
}) => {
    const [render, setRender] = useState(visible);
    const pendingClose = useRef(false);

    const isDismissable = useMemo(() => {
        return (dismissable || true) && !(showSpinnerOverlay || false);
    }, [dismissable, showSpinnerOverlay]);

    useEffect(() => {
        if (!isDismissable || !closeOnEscape) return;

        const handler = (e: KeyboardEvent) => {
            if (e.key === 'Escape') {
                pendingClose.current = true;
                setRender(false);
            }
        };

        window.addEventListener('keydown', handler);
        return () => {
            window.removeEventListener('keydown', handler);
        };
    }, [isDismissable, closeOnEscape, render]);

    useEffect(() => setRender(visible), [visible]);

    const handleClose = () => {
        if (!isDismissable) {
            return;
        }
        pendingClose.current = true;
        setRender(false);
        onDismissed();
    };

    return (
        <ComposedModal
            open={render}
            onClose={() => {
                if (pendingClose.current) {
                    pendingClose.current = false;
                    onDismissed();
                    return true;
                }
                handleClose();
                return true;
            }}
            preventCloseOnClickOutside={!closeOnBackground || !isDismissable}
            size={'md'}
        >
            {isDismissable && <ModalHeader buttonOnClick={handleClose} title={''} />}
            <ModalBody>
                {showSpinnerOverlay && (
                    <div className={'absolute inset-0 flex items-center justify-center'} style={{ zIndex: 2 }}>
                        <Loading withOverlay description={'Loading'} />
                    </div>
                )}
                {children}
            </ModalBody>
        </ComposedModal>
    );
};

const PortaledModal: React.FC<ModalProps> = ({ children, ...props }) => <Modal {...props}>{children}</Modal>;

export default PortaledModal;
