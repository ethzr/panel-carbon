import React, { useRef, useState } from 'react';
import { Modal } from '@carbon/react';
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

    return (
        <DialogContext.Provider value={{ setIcon, setFooter, setIconPosition }}>
            <Modal
                open={open}
                modalHeading={
                    <span style={{ display: 'inline-flex', alignItems: 'center', gap: '0.5rem' }}>
                        {iconPosition !== 'container' && icon}
                        {title}
                    </span>
                }
                modalLabel={description}
                onRequestClose={() => {
                    if (!ignoreClose.current) {
                        onClose();
                    }
                }}
                preventCloseOnClickOutside={preventExternalClose}
                passiveModal
                size={'md'}
            >
                {hideCloseIcon ? null : null}
                {iconPosition === 'container' && icon}
                {children}
                {footer}
            </Modal>
        </DialogContext.Provider>
    );
};
