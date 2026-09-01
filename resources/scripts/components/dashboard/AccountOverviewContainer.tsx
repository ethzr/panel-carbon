import * as React from 'react';
import ContentBox from '@/components/elements/ContentBox';
import UpdatePasswordForm from '@/components/dashboard/forms/UpdatePasswordForm';
import UpdateEmailAddressForm from '@/components/dashboard/forms/UpdateEmailAddressForm';
import ConfigureTwoFactorForm from '@/components/dashboard/forms/ConfigureTwoFactorForm';
import PageContentBlock from '@/components/elements/PageContentBlock';
import MessageBox from '@/components/MessageBox';
import { useLocation } from 'react-router-dom';
import { Column, Grid } from '@carbon/react';

export default () => {
    const { state } = useLocation<undefined | { twoFactorRedirect?: boolean }>();

    return (
        <PageContentBlock title={'Account Overview'}>
            {state?.twoFactorRedirect && (
                <MessageBox title={'2-Factor Required'} type={'error'}>
                    Your account must have two-factor authentication enabled in order to continue.
                </MessageBox>
            )}

            <Grid fullWidth style={{ marginTop: state?.twoFactorRedirect ? '1rem' : '1.5rem', padding: 0 }}>
                <Column sm={4} md={4} lg={5} style={{ marginBottom: '1rem' }}>
                    <ContentBox title={'Update Password'} showFlashes={'account:password'}>
                        <UpdatePasswordForm />
                    </ContentBox>
                </Column>
                <Column sm={4} md={4} lg={5} style={{ marginBottom: '1rem' }}>
                    <ContentBox title={'Update Email Address'} showFlashes={'account:email'}>
                        <UpdateEmailAddressForm />
                    </ContentBox>
                </Column>
                <Column sm={4} md={8} lg={6} style={{ marginBottom: '1rem' }}>
                    <ContentBox title={'Two-Step Verification'}>
                        <ConfigureTwoFactorForm />
                    </ContentBox>
                </Column>
            </Grid>
        </PageContentBlock>
    );
};
