import React, { Suspense } from 'react';
import { Loading } from '@carbon/react';
import ErrorBoundary from '@/components/elements/ErrorBoundary';

export type SpinnerSize = 'small' | 'base' | 'large';

interface Props {
    size?: SpinnerSize;
    centered?: boolean;
    isBlue?: boolean;
}

interface Spinner extends React.FC<Props> {
    Size: Record<'SMALL' | 'BASE' | 'LARGE', SpinnerSize>;
    Suspense: React.FC<Props>;
}

const Spinner: Spinner = ({ centered, size, isBlue: _isBlue }) => {
    const node = <Loading small={size === 'small'} withOverlay={false} description={'Loading'} />;

    return centered ? (
        <div
            className={'flex justify-center items-center'}
            style={{ margin: size === 'large' ? '5rem 0' : '1.5rem 0' }}
        >
            {node}
        </div>
    ) : (
        node
    );
};
Spinner.displayName = 'Spinner';

Spinner.Size = {
    SMALL: 'small',
    BASE: 'base',
    LARGE: 'large',
};

Spinner.Suspense = ({ children, centered = true, size = Spinner.Size.LARGE, ...props }) => (
    <Suspense fallback={<Spinner centered={centered} size={size} {...props} />}>
        <ErrorBoundary>{children}</ErrorBoundary>
    </Suspense>
);
Spinner.Suspense.displayName = 'Spinner.Suspense';

export default Spinner;
