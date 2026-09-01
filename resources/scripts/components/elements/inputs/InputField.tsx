import React, { forwardRef } from 'react';
import classNames from 'classnames';

enum Variant {
    Normal,
    Snug,
    Loose,
}

const Component = forwardRef<HTMLInputElement, React.ComponentProps<'input'> & { variant?: Variant }>(
    ({ className, variant, ...props }, ref) => (
        <input
            ref={ref}
            className={classNames('cds--text-input', variant === Variant.Loose && 'cds--text-input--lg', className)}
            {...props}
        />
    )
);

const InputField = Object.assign(Component, { Variants: Variant });

export default InputField;
