import React from 'react';

const ContentContainer: React.FC<React.HTMLAttributes<HTMLDivElement>> = ({ className, children, ...props }) => (
    <div className={`ptero-content ${className || ''}`} {...props}>
        {children}
    </div>
);
ContentContainer.displayName = 'ContentContainer';

export default ContentContainer;
