import React from 'react';

const SubNavigation: React.FC<React.HTMLAttributes<HTMLDivElement>> = ({ children, className, ...props }) => (
    <nav className={`ptero-subnav ${className || ''}`} aria-label={'Section'} {...props}>
        {children}
    </nav>
);

export default SubNavigation;
