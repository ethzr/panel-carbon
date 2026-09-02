import React from 'react';
import { FormikErrors, FormikTouched } from 'formik';
import { capitalize } from '@/lib/strings';

interface Props {
    errors: FormikErrors<any>;
    touched: FormikTouched<any>;
    name: string;
    children?: string | number | null | undefined;
}

const InputError = ({ errors, touched, name, children }: Props) =>
    touched[name] && errors[name] ? (
        <p className={'cds--form__helper-text input-help error'}>
            {typeof errors[name] === 'string'
                ? capitalize(errors[name] as string)
                : capitalize((errors[name] as unknown as string[])[0])}
        </p>
    ) : (
        <>{children ? <p className={'cds--form__helper-text'}>{children}</p> : null}</>
    );

export default InputError;
