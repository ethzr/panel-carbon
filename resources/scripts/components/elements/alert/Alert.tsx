import React from 'react';
import { InlineNotification } from '@carbon/react';

interface AlertProps {
    type: 'warning' | 'danger';
    className?: string;
    children: React.ReactNode;
}

export default ({ type, className, children }: AlertProps) => {
    return (
        <InlineNotification
            className={className}
            kind={type === 'danger' ? 'error' : 'warning'}
            title={type === 'danger' ? 'Error' : 'Warning'}
            subtitle={typeof children === 'string' ? children : undefined}
            hideCloseButton
            lowContrast
        >
            {typeof children !== 'string' ? children : undefined}
        </InlineNotification>
    );
};
