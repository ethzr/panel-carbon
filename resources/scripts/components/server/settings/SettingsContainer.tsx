import React from 'react';
import TitledGreyBox from '@/components/elements/TitledGreyBox';
import { ServerContext } from '@/state/server';
import { useStoreState } from 'easy-peasy';
import RenameServerBox from '@/components/server/settings/RenameServerBox';
import FlashMessageRender from '@/components/FlashMessageRender';
import Can from '@/components/elements/Can';
import ReinstallServerBox from '@/components/server/settings/ReinstallServerBox';
import Input from '@/components/elements/Input';
import Label from '@/components/elements/Label';
import ServerContentBlock from '@/components/elements/ServerContentBlock';
import isEqual from 'react-fast-compare';
import CopyOnClick from '@/components/elements/CopyOnClick';
import { ip } from '@/lib/formatters';
import { Button } from '@carbon/react';
import { InlineNotification } from '@carbon/react';

export default () => {
    const username = useStoreState((state) => state.user.data!.username);
    const id = ServerContext.useStoreState((state) => state.server.data!.id);
    const uuid = ServerContext.useStoreState((state) => state.server.data!.uuid);
    const node = ServerContext.useStoreState((state) => state.server.data!.node);
    const sftp = ServerContext.useStoreState((state) => state.server.data!.sftpDetails, isEqual);

    return (
        <ServerContentBlock title={'Settings'}>
            <FlashMessageRender byKey={'settings'} className={'mb-4'} />
            <div className={'ptero-split'}>
                <div className={'ptero-stack'}>
                    <Can action={'file.sftp'}>
                        <TitledGreyBox title={'SFTP Details'}>
                            <div className={'ptero-stack'}>
                                <div>
                                    <Label>Server Address</Label>
                                    <CopyOnClick text={`sftp://${ip(sftp.ip)}:${sftp.port}`}>
                                        <Input type={'text'} value={`sftp://${ip(sftp.ip)}:${sftp.port}`} readOnly />
                                    </CopyOnClick>
                                </div>
                                <div>
                                    <Label>Username</Label>
                                    <CopyOnClick text={`${username}.${id}`}>
                                        <Input type={'text'} value={`${username}.${id}`} readOnly />
                                    </CopyOnClick>
                                </div>
                                <InlineNotification
                                    kind={'info'}
                                    lowContrast
                                    hideCloseButton
                                    title={'Password'}
                                    subtitle={'Your SFTP password is the same as the password you use to access this panel.'}
                                />
                                <a href={`sftp://${username}.${id}@${ip(sftp.ip)}:${sftp.port}`}>
                                    <Button kind={'tertiary'} size={'md'}>
                                        Launch SFTP
                                    </Button>
                                </a>
                            </div>
                        </TitledGreyBox>
                    </Can>
                    <TitledGreyBox title={'Debug Information'}>
                        <div className={'ptero-stack'}>
                            <div className={'ptero-toolbar'} style={{ marginTop: 0, justifyContent: 'space-between' }}>
                                <p>Node</p>
                                <code className={'ptero-code'}>{node}</code>
                            </div>
                            <CopyOnClick text={uuid}>
                                <div className={'ptero-toolbar'} style={{ marginTop: 0, justifyContent: 'space-between' }}>
                                    <p>Server ID</p>
                                    <code className={'ptero-code'}>{uuid}</code>
                                </div>
                            </CopyOnClick>
                        </div>
                    </TitledGreyBox>
                </div>
                <div className={'ptero-stack'}>
                    <Can action={'settings.rename'}>
                        <RenameServerBox />
                    </Can>
                    <Can action={'settings.reinstall'}>
                        <ReinstallServerBox />
                    </Can>
                </div>
            </div>
        </ServerContentBlock>
    );
};
