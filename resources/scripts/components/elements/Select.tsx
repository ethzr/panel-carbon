import React, { forwardRef } from 'react';
import classNames from 'classnames';
import { ChevronDown } from '@carbon/react/icons';

interface Props {
    hideDropdownArrow?: boolean;
}

type SelectProps = Props & React.SelectHTMLAttributes<HTMLSelectElement>;

const Select = forwardRef<HTMLSelectElement, SelectProps>(
    ({ hideDropdownArrow, className, children, ...props }, ref) => (
        <div className={'cds--select ptero-select'}>
            <div className={'cds--select-input__wrapper'}>
                <select ref={ref} className={classNames('cds--select-input', className)} {...props}>
                    {children}
                </select>
                {!hideDropdownArrow && <ChevronDown className={'cds--select__arrow'} size={16} />}
            </div>
        </div>
    )
);

Select.displayName = 'Select';

export default Select;
