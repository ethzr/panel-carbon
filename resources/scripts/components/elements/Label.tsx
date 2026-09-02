import React from 'react';
import classNames from 'classnames';

interface Props extends React.LabelHTMLAttributes<HTMLLabelElement> {
    isLight?: boolean;
}

const Label = ({ className, children, ...props }: Props) => (
    <label className={classNames('cds--label', className)} {...props}>
        {children}
    </label>
);

export default Label;
