import React from 'react';
import { ServerContext } from '@/state/server';
import { Checkbox } from '@carbon/react';

export const FileActionCheckbox = Checkbox;

export default ({ name }: { name: string }) => {
    const isChecked = ServerContext.useStoreState((state) => state.files.selectedFiles.indexOf(name) >= 0);
    const appendSelectedFile = ServerContext.useStoreActions((actions) => actions.files.appendSelectedFile);
    const removeSelectedFile = ServerContext.useStoreActions((actions) => actions.files.removeSelectedFile);

    return (
        <Checkbox
            id={`file_select_${name}`}
            labelText={''}
            hideLabel
            name={'selectedFiles'}
            value={name}
            checked={isChecked}
            onChange={(_evt, data: { checked: boolean }) => {
                if (data.checked) {
                    appendSelectedFile(name);
                } else {
                    removeSelectedFile(name);
                }
            }}
        />
    );
};
