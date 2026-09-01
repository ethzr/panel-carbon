import React, { useMemo } from 'react';
import { v4 } from 'uuid';
import { Toggle } from '@carbon/react';

export interface SwitchProps {
    name: string;
    label?: string;
    description?: string;
    defaultChecked?: boolean;
    readOnly?: boolean;
    onChange?: (e: React.ChangeEvent<HTMLInputElement>) => void;
    children?: React.ReactNode;
}

const Switch = ({ name, label, description, defaultChecked, readOnly, onChange, children }: SwitchProps) => {
    const uuid = useMemo(() => v4(), []);

    if (children) {
        return <div className={'cds--form-item'}>{children}</div>;
    }

    return (
        <div className={'cds--form-item'}>
            <Toggle
                id={uuid}
                name={name}
                labelText={label || name}
                hideLabel={!label}
                defaultToggled={defaultChecked}
                disabled={readOnly}
                onToggle={(checked) => {
                    if (!onChange) {
                        return;
                    }
                    onChange({
                        target: { name, checked, type: 'checkbox' },
                        currentTarget: { name, checked, type: 'checkbox' },
                    } as React.ChangeEvent<HTMLInputElement>);
                }}
            />
            {description && <p className={'cds--form__helper-text'}>{description}</p>}
        </div>
    );
};

export default Switch;
