import React, { forwardRef } from 'react';
import classNames from 'classnames';

export interface Props {
    isLight?: boolean;
    hasError?: boolean;
}

type InputProps = Props & React.InputHTMLAttributes<HTMLInputElement>;

const Input = forwardRef<HTMLInputElement, InputProps>(({ isLight, hasError, className, type, ...props }, ref) => {
    if (type === 'checkbox') {
        return <input ref={ref} type={'checkbox'} className={classNames('cds--checkbox', className)} {...props} />;
    }

    if (type === 'radio') {
        return <input ref={ref} type={'radio'} className={classNames('cds--radio-button', className)} {...props} />;
    }

    return (
        <input
            ref={ref}
            type={type}
            className={classNames('cds--text-input', isLight && 'cds--text-input--light', hasError && 'cds--text-input--invalid', className)}
            data-invalid={hasError || undefined}
            {...props}
        />
    );
});

Input.displayName = 'Input';

type TextareaProps = Props & React.TextareaHTMLAttributes<HTMLTextAreaElement>;

const Textarea = forwardRef<HTMLTextAreaElement, TextareaProps>(({ isLight, hasError, className, ...props }, ref) => (
    <textarea
        ref={ref}
        className={classNames('cds--text-area', isLight && 'cds--text-input--light', hasError && 'cds--text-area--invalid', className)}
        data-invalid={hasError || undefined}
        {...props}
    />
));

Textarea.displayName = 'Textarea';

export { Textarea };
export default Input;
