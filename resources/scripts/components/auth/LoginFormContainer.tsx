import React, { forwardRef } from 'react';
import { Form } from 'formik';
import FlashMessageRender from '@/components/FlashMessageRender';
import { Tile } from '@carbon/react';

type Props = React.DetailedHTMLProps<React.FormHTMLAttributes<HTMLFormElement>, HTMLFormElement> & {
    title?: string;
};

export default forwardRef<HTMLFormElement, Props>(({ title, children, ...props }, ref) => (
    <div className={'ptero-auth'}>
        <div className={'ptero-auth__card'}>
            {title && <h1 className={'cds--productive-heading-04 ptero-auth__title'}>{title}</h1>}
            <FlashMessageRender className={'ptero-auth__flash'} />
            <Form {...props} ref={ref}>
                <Tile className={'ptero-auth__tile'}>{children}</Tile>
            </Form>
            <p className={'cds--label ptero-auth__copy'}>
                &copy; 2015 - {new Date().getFullYear()}&nbsp;
                <a rel={'noopener nofollow noreferrer'} href={'https://pterodactyl.io'} target={'_blank'}>
                    Pterodactyl Software
                </a>
            </p>
        </div>
    </div>
));
