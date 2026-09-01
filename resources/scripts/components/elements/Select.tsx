import React, { forwardRef } from 'react';
import classNames from 'classnames';

interface Props {
    hideDropdownArrow?: boolean;
}

type SelectProps = Props & React.SelectHTMLAttributes<HTMLSelectElement>;

const Select = forwardRef<HTMLSelectElement, SelectProps>(({ hideDropdownArrow, className, children, ...props }, ref) => (
    <select ref={ref} className={classNames('cds--select-input', className)} {...props}>
        {children}
    </select>
));

Select.displayName = 'Select';

export default Select;
