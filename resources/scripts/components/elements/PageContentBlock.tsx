import React, { useEffect } from 'react';
import { CSSTransition } from 'react-transition-group';
import FlashMessageRender from '@/components/FlashMessageRender';
import { Grid, Column } from '@carbon/react';

export interface PageContentBlockProps {
    title?: string;
    className?: string;
    showFlashKey?: string;
}

const PageContentBlock: React.FC<PageContentBlockProps> = ({ title, showFlashKey, className, children }) => {
    useEffect(() => {
        if (title) {
            document.title = title;
        }
    }, [title]);

    return (
        <CSSTransition timeout={150} classNames={'fade'} appear in>
            <>
                <Grid className={className} fullWidth style={{ paddingTop: '1.5rem', paddingBottom: '2rem' }}>
                    <Column lg={16} md={8} sm={4}>
                        {showFlashKey && <FlashMessageRender byKey={showFlashKey} className={'mb-4'} />}
                        {children}
                    </Column>
                </Grid>
                <p className={'cds--label'} style={{ textAlign: 'center', display: 'block', marginBottom: '1rem' }}>
                    <a rel={'noopener nofollow noreferrer'} href={'https://pterodactyl.io'} target={'_blank'}>
                        Pterodactyl&reg;
                    </a>
                    &nbsp;&copy; 2015 - {new Date().getFullYear()}
                </p>
            </>
        </CSSTransition>
    );
};

export default PageContentBlock;
