import React, { useState } from 'react';
import { Subuser } from '@/state/server/subusers';
import { FontAwesomeIcon } from '@fortawesome/react-fontawesome';
import { faPencilAlt, faUnlockAlt, faUserLock } from '@fortawesome/free-solid-svg-icons';
import RemoveSubuserButton from '@/components/server/users/RemoveSubuserButton';
import EditSubuserModal from '@/components/server/users/EditSubuserModal';
import Can from '@/components/elements/Can';
import { useStoreState } from 'easy-peasy';
import GreyRowBox from '@/components/elements/GreyRowBox';
import { Button } from '@carbon/react';

interface Props {
    subuser: Subuser;
}

export default ({ subuser }: Props) => {
    const uuid = useStoreState((state) => state.user!.data!.uuid);
    const [visible, setVisible] = useState(false);

    return (
        <GreyRowBox $hoverable={false}>
            <EditSubuserModal subuser={subuser} visible={visible} onModalDismissed={() => setVisible(false)} />
            <img
                className={'ptero-resource-row__icon'}
                src={`${subuser.image}?s=400`}
                alt={''}
                style={{ borderRadius: '50%', objectFit: 'cover' }}
            />
            <div className={'ptero-resource-row__body'}>
                <p>{subuser.email}</p>
            </div>
            <div className={'ptero-resource-row__meta'}>
                <FontAwesomeIcon
                    icon={subuser.twoFactorEnabled ? faUserLock : faUnlockAlt}
                    style={!subuser.twoFactorEnabled ? { color: 'var(--cds-support-error)' } : undefined}
                />
                <span className={'ptero-resource-row__label'}>2FA</span>
            </div>
            <div className={'ptero-resource-row__meta'}>
                <p>{subuser.permissions.filter((permission) => permission !== 'websocket.connect').length}</p>
                <span className={'ptero-resource-row__label'}>Permissions</span>
            </div>
            {subuser.uuid !== uuid && (
                <div className={'ptero-resource-row__actions'}>
                    <Can action={'user.update'}>
                        <Button
                            kind={'ghost'}
                            size={'sm'}
                            hasIconOnly
                            iconDescription={'Edit subuser'}
                            renderIcon={undefined}
                            onClick={() => setVisible(true)}
                        >
                            <FontAwesomeIcon icon={faPencilAlt} />
                        </Button>
                    </Can>
                    <Can action={'user.delete'}>
                        <RemoveSubuserButton subuser={subuser} />
                    </Can>
                </div>
            )}
        </GreyRowBox>
    );
};
