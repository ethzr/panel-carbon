import React from 'react';
import Spinner from '@/components/elements/Spinner';

const InputSpinner = ({ visible, children }: { visible: boolean; children: React.ReactNode }) => (
    <div className={'ptero-input-spinner'}>
        {visible && (
            <div className={'ptero-input-spinner__indicator'}>
                <Spinner size={'small'} />
            </div>
        )}
        {children}
    </div>
);

export default InputSpinner;
