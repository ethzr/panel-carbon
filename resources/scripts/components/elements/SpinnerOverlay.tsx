import React from 'react';
import Spinner, { SpinnerSize } from '@/components/elements/Spinner';
import Fade from '@/components/elements/Fade';
import { Loading } from '@carbon/react';

interface Props {
    visible: boolean;
    fixed?: boolean;
    size?: SpinnerSize;
    backgroundOpacity?: number;
}

const SpinnerOverlay: React.FC<Props> = ({ size, fixed, visible, backgroundOpacity, children }) => (
    <Fade timeout={150} in={visible} unmountOnExit>
        <div
            className={`${fixed ? 'fixed' : 'absolute'} top-0 left-0 flex items-center justify-center w-full h-full rounded flex-col z-40`}
            style={{ background: `rgba(0, 0, 0, ${backgroundOpacity || 0.45})` }}
        >
            {size === 'small' ? <Spinner size={size} /> : <Loading withOverlay={false} description={'Loading'} />}
            {children &&
                (typeof children === 'string' ? <p className={'mt-4 cds--label'}>{children}</p> : children)}
        </div>
    </Fade>
);

export default SpinnerOverlay;
