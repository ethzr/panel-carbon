import React from 'react';
import FlashMessageRender from '@/components/FlashMessageRender';
import SpinnerOverlay from '@/components/elements/SpinnerOverlay';
import { Tile } from '@carbon/react';

type Props = Readonly<
    React.DetailedHTMLProps<React.HTMLAttributes<HTMLDivElement>, HTMLDivElement> & {
        title?: string;
        borderColor?: string;
        showFlashes?: string | boolean;
        showLoadingOverlay?: boolean;
    }
>;

const ContentBox = ({ title, borderColor, showFlashes, showLoadingOverlay, children, ...props }: Props) => (
    <div {...props}>
        {title && <h2 className={'cds--heading-compact-02'} style={{ marginBottom: '1rem' }}>{title}</h2>}
        {showFlashes && (
            <FlashMessageRender byKey={typeof showFlashes === 'string' ? showFlashes : undefined} className={'mb-4'} />
        )}
        <Tile className={'ptero-tile relative'} style={borderColor ? { borderTop: `4px solid ${borderColor}` } : undefined}>
            <SpinnerOverlay visible={showLoadingOverlay || false} />
            {children}
        </Tile>
    </div>
);

export default ContentBox;
