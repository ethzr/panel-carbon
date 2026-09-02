import React, { forwardRef } from 'react';
import { Field as FormikField, FieldProps } from 'formik';
import { PasswordInput, TextArea, TextInput } from '@carbon/react';

interface OwnProps {
    name: string;
    light?: boolean;
    label?: string;
    description?: string;
    validate?: (value: any) => undefined | string | Promise<any>;
}

type Props = OwnProps & Omit<React.InputHTMLAttributes<HTMLInputElement>, 'name'>;

const Field = forwardRef<HTMLInputElement, Props>(
    ({ id, name, light = false, label, description, validate, type, ...props }, ref) => (
        <FormikField innerRef={ref} name={name} validate={validate}>
            {({ field, form: { errors, touched } }: FieldProps) => {
                const invalid = !!(touched[field.name] && errors[field.name]);
                const invalidText = invalid
                    ? (errors[field.name] as string).charAt(0).toUpperCase() + (errors[field.name] as string).slice(1)
                    : undefined;
                const inputId = id || name;
                const shared = {
                    id: inputId,
                    labelText: label || '',
                    helperText: !invalid && description ? description : undefined,
                    invalid,
                    invalidText,
                    disabled: props.disabled,
                    placeholder: props.placeholder,
                    autoFocus: props.autoFocus,
                    autoComplete: props.autoComplete,
                    ...field,
                };

                if (type === 'textarea') {
                    return <TextArea {...shared} rows={4} />;
                }

                if (type === 'password') {
                    return <PasswordInput {...shared} hidePasswordLabel={'Hide password'} showPasswordLabel={'Show password'} />;
                }

                return <TextInput {...shared} type={type || 'text'} light={light} />;
            }}
        </FormikField>
    )
);
Field.displayName = 'Field';

export default Field;
