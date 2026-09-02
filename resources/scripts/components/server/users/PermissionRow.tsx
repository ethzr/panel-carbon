import React from 'react';
import { useField } from 'formik';
import { useStoreState } from 'easy-peasy';
import { Checkbox } from '@carbon/react';

interface Props {
    permission: string;
    disabled: boolean;
}

const PermissionRow = ({ permission, disabled }: Props) => {
    const [key, pkey] = permission.split('.', 2);
    const permissions = useStoreState((state) => state.permissions.data);
    const [field, , helpers] = useField<string[]>('permissions');

    const checked = (field.value || []).includes(permission);
    const description = permissions[key].keys[pkey];

    return (
        <Checkbox
            id={`permission_${permission}`}
            className={disabled ? 'ptero-permission is-disabled' : 'ptero-permission'}
            labelText={pkey}
            helperText={description.length > 0 ? description : undefined}
            checked={checked}
            disabled={disabled}
            onChange={(_event, { checked: nextChecked }) => {
                const set = new Set(field.value || []);
                nextChecked ? set.add(permission) : set.delete(permission);
                helpers.setTouched(true);
                helpers.setValue(Array.from(set));
            }}
        />
    );
};

export default PermissionRow;
