import * as React from 'react';
import { InlineNotification } from '@carbon/react';

export type FlashMessageType = 'success' | 'info' | 'warning' | 'error';

interface Props {
    title?: string;
    children: string;
    type?: FlashMessageType;
}

const MessageBox = ({ title, children, type }: Props) => (
    <InlineNotification
        kind={type === 'error' ? 'error' : type === 'success' ? 'success' : type === 'warning' ? 'warning' : 'info'}
        title={title || (type ? type.toUpperCase() : 'Notice')}
        subtitle={children}
        hideCloseButton
        lowContrast
        role={'alert'}
    />
);
MessageBox.displayName = 'MessageBox';

export default MessageBox;
