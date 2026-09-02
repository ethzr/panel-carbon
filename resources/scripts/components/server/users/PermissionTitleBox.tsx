import React, { memo, useCallback } from 'react';
import { useField } from 'formik';
import TitledGreyBox from '@/components/elements/TitledGreyBox';
import { Checkbox } from '@carbon/react';
import isEqual from 'react-fast-compare';

interface Props {
    isEditable: boolean;
    title: string;
    permissions: string[];
    className?: string;
}

const PermissionTitleBox: React.FC<Props> = memo(({ isEditable, title, permissions, className, children }) => {
    const [{ value }, , { setValue }] = useField<string[]>('permissions');

    const onCheckboxClicked = useCallback(
        (_event: React.ChangeEvent<HTMLInputElement>, data: { checked: boolean }) => {
            if (data.checked) {
                setValue([...value, ...permissions.filter((p) => !value.includes(p))]);
            } else {
                setValue(value.filter((p) => !permissions.includes(p)));
            }
        },
        [permissions, value]
    );

    return (
        <TitledGreyBox
            title={
                <div className={'ptero-stack ptero-stack--row'} style={{ justifyContent: 'space-between', width: '100%' }}>
                    <p>{title}</p>
                    {isEditable && (
                        <Checkbox
                            id={`permission_group_${title}`}
                            hideLabel
                            labelText={`Toggle all ${title} permissions`}
                            checked={permissions.every((p) => value.includes(p))}
                            onChange={onCheckboxClicked}
                        />
                    )}
                </div>
            }
            className={className}
        >
            {children}
        </TitledGreyBox>
    );
}, isEqual);

export default PermissionTitleBox;
