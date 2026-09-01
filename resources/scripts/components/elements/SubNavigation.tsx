import React from 'react';
import classNames from 'classnames';

const SubNavigation: React.FC<React.HTMLAttributes<HTMLDivElement>> = ({ children, className, ...props }) => (
    <nav className={classNames('cds--tabs', className)} aria-label={'Section'} {...props}>
        <div className={'cds--tab--list'} role={'tablist'}>
            {children}
        </div>
    </nav>
);

export default SubNavigation;
