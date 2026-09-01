import React from 'react';
import classNames from 'classnames';

interface CodeProps {
    dark?: boolean | undefined;
    className?: string;
    children: React.ReactChild | React.ReactFragment | React.ReactPortal;
}

export default ({ dark, className, children }: CodeProps) => (
    <code
        className={classNames('cds--tag', dark ? 'cds--tag--gray' : 'cds--tag--blue', className)}
        style={{ display: 'inline-block', fontFamily: 'ibm-plex-mono, monospace' }}
    >
        {children}
    </code>
);
