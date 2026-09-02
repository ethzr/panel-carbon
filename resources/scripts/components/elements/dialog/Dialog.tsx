import React, { useRef, useState } from 'react';
import { ComposedModal, ModalBody, ModalFooter, ModalHeader } from '@carbon/react';
import { DialogContext, IconPosition, RenderDialogProps } from './';

export default ({
    open,
    title,
    description,
    onClose,
    hideCloseIcon,
    preventExternalClose,
    children,
}: RenderDialogProps) => {
    const [icon, setIcon] = useState<React.ReactNode>();
    const [footer, setFooter] = useState<React.ReactNode>();
    const [iconPosition, setIconPosition] = useState<IconPosition>('title');
    const ignoreClose = useRef(preventExternalClose);

    ignoreClose.current = preventExternalClose;

    const handleClose = () => {
        if (!ignoreClose.current) {
            onClose();
        }
    };

    return (
        <DialogContext.Provider value={{ setIcon, setFooter, setIconPosition }}>
            <ComposedModal
                open={open}
                size={'md'}
                className={hideCloseIcon ? 'ptero-modal--no-close' : undefined}
                preventCloseOnClickOutside={preventExternalClose}
                onClose={() => {
                    handleClose();
                    return true;
                }}
            >
                <ModalHeader title={title} label={description} iconDescription={'Close'} />
                <ModalBody>
                    {iconPosition === 'container' && icon}
                    {iconPosition !== 'container' && icon}
                    {children}
                </ModalBody>
                {footer ? <ModalFooter>{footer}</ModalFooter> : null}
            </ComposedModal>
        </DialogContext.Provider>
    );
};
