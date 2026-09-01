import React, { forwardRef } from 'react';
import { Form } from 'formik';
import FlashMessageRender from '@/components/FlashMessageRender';
import { Column, Grid, Tile } from '@carbon/react';

type Props = React.DetailedHTMLProps<React.FormHTMLAttributes<HTMLFormElement>, HTMLFormElement> & {
    title?: string;
};

export default forwardRef<HTMLFormElement, Props>(({ title, ...props }, ref) => (
    <Grid fullWidth>
        <Column sm={4} md={6} lg={8} style={{ margin: '2rem auto', maxWidth: '40rem' }}>
            {title && (
                <h2 className={'cds--productive-heading-04'} style={{ textAlign: 'center', marginBottom: '1rem' }}>
                    {title}
                </h2>
            )}
            <FlashMessageRender className={'mb-2'} />
            <Form {...props} ref={ref}>
                <Tile className={'ptero-tile'}>
                    <div style={{ textAlign: 'center', marginBottom: '1.5rem' }}>
                        <img src={'/assets/svgs/pterodactyl.svg'} alt={''} style={{ width: '8rem' }} />
                    </div>
                    {props.children}
                </Tile>
            </Form>
            <p className={'cds--label'} style={{ textAlign: 'center', display: 'block', marginTop: '1rem' }}>
                &copy; 2015 - {new Date().getFullYear()}&nbsp;
                <a rel={'noopener nofollow noreferrer'} href={'https://pterodactyl.io'} target={'_blank'}>
                    Pterodactyl Software
                </a>
            </p>
        </Column>
    </Grid>
));
