import React, { memo, useCallback, useState } from 'react';
import isEqual from 'react-fast-compare';
import { FontAwesomeIcon } from '@fortawesome/react-fontawesome';
import { faNetworkWired } from '@fortawesome/free-solid-svg-icons';
import InputSpinner from '@/components/elements/InputSpinner';
import { Textarea } from '@/components/elements/Input';
import Can from '@/components/elements/Can';
import { Button } from '@carbon/react';
import GreyRowBox from '@/components/elements/GreyRowBox';
import { Allocation } from '@/api/server/getServer';
import { debounce } from 'debounce';
import setServerAllocationNotes from '@/api/server/network/setServerAllocationNotes';
import { useFlashKey } from '@/plugins/useFlash';
import { ServerContext } from '@/state/server';
import CopyOnClick from '@/components/elements/CopyOnClick';
import DeleteAllocationButton from '@/components/server/network/DeleteAllocationButton';
import setPrimaryServerAllocation from '@/api/server/network/setPrimaryServerAllocation';
import getServerAllocations from '@/api/swr/getServerAllocations';
import { ip } from '@/lib/formatters';
import Code from '@/components/elements/Code';

interface Props {
    allocation: Allocation;
}

const AllocationRow = ({ allocation }: Props) => {
    const [loading, setLoading] = useState(false);
    const { clearFlashes, clearAndAddHttpError } = useFlashKey('server:network');
    const uuid = ServerContext.useStoreState((state) => state.server.data!.uuid);
    const { mutate } = getServerAllocations();

    const onNotesChanged = useCallback((id: number, notes: string) => {
        mutate((data) => data?.map((a) => (a.id === id ? { ...a, notes } : a)), false);
    }, []);

    const setAllocationNotes = debounce((notes: string) => {
        setLoading(true);
        clearFlashes();

        setServerAllocationNotes(uuid, allocation.id, notes)
            .then(() => onNotesChanged(allocation.id, notes))
            .catch((error) => clearAndAddHttpError(error))
            .then(() => setLoading(false));
    }, 750);

    const setPrimaryAllocation = () => {
        clearFlashes();
        mutate((data) => data?.map((a) => ({ ...a, isDefault: a.id === allocation.id })), false);

        setPrimaryServerAllocation(uuid, allocation.id).catch((error) => {
            clearAndAddHttpError(error);
            mutate();
        });
    };

    return (
        <GreyRowBox $hoverable={false}>
            <div className={'ptero-resource-row__icon'}>
                <FontAwesomeIcon icon={faNetworkWired} />
            </div>
            <div className={'ptero-resource-row__meta'}>
                {allocation.alias ? (
                    <CopyOnClick text={allocation.alias}>
                        <Code dark>{allocation.alias}</Code>
                    </CopyOnClick>
                ) : (
                    <CopyOnClick text={ip(allocation.ip)}>
                        <Code dark>{ip(allocation.ip)}</Code>
                    </CopyOnClick>
                )}
                <span className={'ptero-resource-row__label'}>{allocation.alias ? 'Hostname' : 'IP Address'}</span>
            </div>
            <div className={'ptero-resource-row__meta'}>
                <Code dark>{allocation.port}</Code>
                <span className={'ptero-resource-row__label'}>Port</span>
            </div>
            <div className={'ptero-resource-row__body'}>
                <InputSpinner visible={loading}>
                    <Textarea
                        placeholder={'Notes'}
                        defaultValue={allocation.notes || undefined}
                        onChange={(e) => setAllocationNotes(e.currentTarget.value)}
                    />
                </InputSpinner>
            </div>
            <div className={'ptero-resource-row__actions'}>
                {allocation.isDefault ? (
                    <Button kind={'primary'} size={'sm'} disabled>
                        Primary
                    </Button>
                ) : (
                    <>
                        <Can action={'allocation.delete'}>
                            <DeleteAllocationButton allocation={allocation.id} />
                        </Can>
                        <Can action={'allocation.update'}>
                            <Button kind={'ghost'} size={'sm'} onClick={setPrimaryAllocation}>
                                Make Primary
                            </Button>
                        </Can>
                    </>
                )}
            </div>
        </GreyRowBox>
    );
};

export default memo(AllocationRow, isEqual);
