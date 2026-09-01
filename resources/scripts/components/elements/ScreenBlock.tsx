import React from 'react';
import PageContentBlock from '@/components/elements/PageContentBlock';
import { Button, Tile } from '@carbon/react';
import { ArrowLeft, Restart } from '@carbon/react/icons';
import NotFoundSvg from '@/assets/images/not_found.svg';
import ServerErrorSvg from '@/assets/images/server_error.svg';

interface BaseProps {
    title: string;
    image: string;
    message: string;
    onRetry?: () => void;
    onBack?: () => void;
}

interface PropsWithRetry extends BaseProps {
    onRetry?: () => void;
    onBack?: never;
}

interface PropsWithBack extends BaseProps {
    onBack?: () => void;
    onRetry?: never;
}

export type ScreenBlockProps = PropsWithBack | PropsWithRetry;

const ScreenBlock = ({ title, image, message, onBack, onRetry }: ScreenBlockProps) => (
    <PageContentBlock>
        <div style={{ display: 'flex', justifyContent: 'center' }}>
            <Tile className={'ptero-tile'} style={{ maxWidth: '40rem', textAlign: 'center', position: 'relative' }}>
                {(typeof onBack === 'function' || typeof onRetry === 'function') && (
                    <div style={{ position: 'absolute', left: '1rem', top: '1rem' }}>
                        <Button
                            kind={'ghost'}
                            hasIconOnly
                            size={'sm'}
                            renderIcon={onRetry ? Restart : ArrowLeft}
                            iconDescription={onRetry ? 'Retry' : 'Back'}
                            onClick={() => (onRetry ? onRetry() : onBack ? onBack() : null)}
                        />
                    </div>
                )}
                <img src={image} alt={''} style={{ width: '66%', height: 'auto', margin: '0 auto' }} />
                <h2 className={'cds--productive-heading-05'} style={{ marginTop: '1.5rem' }}>
                    {title}
                </h2>
                <p className={'cds--body-compact-01'} style={{ marginTop: '0.5rem' }}>
                    {message}
                </p>
            </Tile>
        </div>
    </PageContentBlock>
);

type ServerErrorProps = (Omit<PropsWithBack, 'image' | 'title'> | Omit<PropsWithRetry, 'image' | 'title'>) & {
    title?: string;
};

const ServerError = ({ title, ...props }: ServerErrorProps) => (
    <ScreenBlock title={title || 'Something went wrong'} image={ServerErrorSvg} {...props} />
);

const NotFound = ({ title, message, onBack }: Partial<Pick<ScreenBlockProps, 'title' | 'message' | 'onBack'>>) => (
    <ScreenBlock
        title={title || '404'}
        image={NotFoundSvg}
        message={message || 'The requested resource was not found.'}
        onBack={onBack}
    />
);

export { ServerError, NotFound };
export default ScreenBlock;
