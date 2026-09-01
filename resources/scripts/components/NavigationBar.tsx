import * as React from 'react';
import { useState } from 'react';
import { Link, useHistory } from 'react-router-dom';
import { useStoreState } from 'easy-peasy';
import { ApplicationStore } from '@/state';
import SearchContainer from '@/components/dashboard/search/SearchContainer';
import http from '@/api/http';
import SpinnerOverlay from '@/components/elements/SpinnerOverlay';
import { Header, HeaderGlobalAction, HeaderGlobalBar, HeaderName, SkipToContent } from '@carbon/react';
import { Dashboard, Logout, Settings, UserAvatarFilledAlt } from '@carbon/react/icons';

export default () => {
    const history = useHistory();
    const name = useStoreState((state: ApplicationStore) => state.settings.data!.name);
    const rootAdmin = useStoreState((state: ApplicationStore) => state.user.data!.rootAdmin);
    const [isLoggingOut, setIsLoggingOut] = useState(false);

    const onTriggerLogout = () => {
        setIsLoggingOut(true);
        http.post('/auth/logout').finally(() => {
            // @ts-expect-error this is valid
            window.location = '/';
        });
    };

    return (
        <Header aria-label={name}>
            <SpinnerOverlay visible={isLoggingOut} fixed />
            <SkipToContent />
            <HeaderName element={Link} to={'/'} prefix="">
                {name}
            </HeaderName>
            <HeaderGlobalBar>
                <SearchContainer />
                <HeaderGlobalAction aria-label={'Dashboard'} tooltipAlignment={'end'} onClick={() => history.push('/')}>
                    <Dashboard size={20} />
                </HeaderGlobalAction>
                {rootAdmin && (
                    <HeaderGlobalAction
                        aria-label={'Admin'}
                        onClick={() => {
                            window.location.href = '/admin';
                        }}
                    >
                        <Settings size={20} />
                    </HeaderGlobalAction>
                )}
                <HeaderGlobalAction aria-label={'Account Settings'} onClick={() => history.push('/account')}>
                    <UserAvatarFilledAlt size={20} />
                </HeaderGlobalAction>
                <HeaderGlobalAction aria-label={'Sign Out'} onClick={onTriggerLogout} tooltipAlignment={'end'}>
                    <Logout size={20} />
                </HeaderGlobalAction>
            </HeaderGlobalBar>
        </Header>
    );
};
