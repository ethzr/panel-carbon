import React, { useEffect } from 'react';
import ContentBox from '@/components/elements/ContentBox';
import SpinnerOverlay from '@/components/elements/SpinnerOverlay';
import FlashMessageRender from '@/components/FlashMessageRender';
import PageContentBlock from '@/components/elements/PageContentBlock';
import GreyRowBox from '@/components/elements/GreyRowBox';
import { useSSHKeys } from '@/api/account/ssh-keys';
import { useFlashKey } from '@/plugins/useFlash';
import { FontAwesomeIcon } from '@fortawesome/react-fontawesome';
import { faKey } from '@fortawesome/free-solid-svg-icons';
import { format } from 'date-fns';
import CreateSSHKeyForm from '@/components/dashboard/ssh/CreateSSHKeyForm';
import DeleteSSHKeyButton from '@/components/dashboard/ssh/DeleteSSHKeyButton';

export default () => {
    const { clearAndAddHttpError } = useFlashKey('account');
    const { data, isValidating, error } = useSSHKeys({
        revalidateOnMount: true,
        revalidateOnFocus: false,
    });

    useEffect(() => {
        clearAndAddHttpError(error);
    }, [error]);

    return (
        <PageContentBlock title={'SSH Keys'}>
            <FlashMessageRender byKey={'account'} />
            <div className={'ptero-split'} style={{ marginTop: '1.5rem' }}>
                <ContentBox title={'Add SSH Key'}>
                    <CreateSSHKeyForm />
                </ContentBox>
                <ContentBox title={'SSH Keys'}>
                    <SpinnerOverlay visible={!data && isValidating} />
                    {!data || !data.length ? (
                        <p className={'ptero-empty'}>{!data ? 'Loading...' : 'No SSH Keys exist for this account.'}</p>
                    ) : (
                        data.map((key) => (
                            <GreyRowBox key={key.fingerprint} $hoverable={false}>
                                <div className={'ptero-resource-row__icon'}>
                                    <FontAwesomeIcon icon={faKey} />
                                </div>
                                <div className={'ptero-resource-row__body'}>
                                    <p>{key.name}</p>
                                    <p className={'ptero-code'} style={{ display: 'inline-block', marginTop: '0.25rem' }}>
                                        SHA256:{key.fingerprint}
                                    </p>
                                    <p className={'ptero-muted'}>Added on: {format(key.createdAt, 'MMM do, yyyy HH:mm')}</p>
                                </div>
                                <DeleteSSHKeyButton name={key.name} fingerprint={key.fingerprint} />
                            </GreyRowBox>
                        ))
                    )}
                </ContentBox>
            </div>
        </PageContentBlock>
    );
};
