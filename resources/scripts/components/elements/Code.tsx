import React from 'react';
import classNames from 'classnames';

interface CodeProps {
    dark?: boolean | undefined;
    className?: string;
    children: React.ReactChild | React.ReactFragment | React.ReactPortal;
}

export default ({ className, children }: CodeProps) => (
    <code className={classNames('ptero-code', className)}>{children}</code>
);
