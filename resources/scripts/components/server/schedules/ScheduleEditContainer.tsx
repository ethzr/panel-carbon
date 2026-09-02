import React, { useCallback, useEffect, useState } from 'react';
import { useHistory, useParams } from 'react-router-dom';
import getServerSchedule from '@/api/server/schedules/getServerSchedule';
import Spinner from '@/components/elements/Spinner';
import FlashMessageRender from '@/components/FlashMessageRender';
import EditScheduleModal from '@/components/server/schedules/EditScheduleModal';
import NewTaskButton from '@/components/server/schedules/NewTaskButton';
import DeleteScheduleButton from '@/components/server/schedules/DeleteScheduleButton';
import Can from '@/components/elements/Can';
import useFlash from '@/plugins/useFlash';
import { ServerContext } from '@/state/server';
import PageContentBlock from '@/components/elements/PageContentBlock';
import { Button } from '@/components/elements/button/index';
import ScheduleTaskRow from '@/components/server/schedules/ScheduleTaskRow';
import isEqual from 'react-fast-compare';
import { format } from 'date-fns';
import ScheduleCronRow from '@/components/server/schedules/ScheduleCronRow';
import RunScheduleButton from '@/components/server/schedules/RunScheduleButton';

interface Params {
    id: string;
}

const CronBox = ({ title, value }: { title: string; value: string }) => (
    <div className={'ptero-inset'}>
        <p className={'ptero-resource-row__label'}>{title}</p>
        <p className={'ptero-stat__value'}>{value}</p>
    </div>
);

const ActivePill = ({ active }: { active: boolean }) => (
    <span className={active ? 'cds--tag cds--tag--green cds--tag--sm' : 'cds--tag cds--tag--red cds--tag--sm'}>
        {active ? 'Active' : 'Inactive'}
    </span>
);

export default () => {
    const history = useHistory();
    const { id: scheduleId } = useParams<Params>();

    const id = ServerContext.useStoreState((state) => state.server.data!.id);
    const uuid = ServerContext.useStoreState((state) => state.server.data!.uuid);

    const { clearFlashes, clearAndAddHttpError } = useFlash();
    const [isLoading, setIsLoading] = useState(true);
    const [showEditModal, setShowEditModal] = useState(false);

    const schedule = ServerContext.useStoreState(
        (st) => st.schedules.data.find((s) => s.id === Number(scheduleId)),
        isEqual
    );
    const appendSchedule = ServerContext.useStoreActions((actions) => actions.schedules.appendSchedule);

    useEffect(() => {
        if (schedule?.id === Number(scheduleId)) {
            setIsLoading(false);
            return;
        }

        clearFlashes('schedules');
        getServerSchedule(uuid, Number(scheduleId))
            .then((schedule) => appendSchedule(schedule))
            .catch((error) => {
                console.error(error);
                clearAndAddHttpError({ error, key: 'schedules' });
            })
            .then(() => setIsLoading(false));
    }, [scheduleId]);

    const toggleEditModal = useCallback(() => {
        setShowEditModal((s) => !s);
    }, []);

    return (
        <PageContentBlock title={'Schedules'}>
            <FlashMessageRender byKey={'schedules'} />
            {!schedule || isLoading ? (
                <Spinner size={'large'} centered />
            ) : (
                <>
                    <ScheduleCronRow cron={schedule.cron} className={'ptero-inset'} />
                    <div className={'ptero-tile'}>
                        <div className={'ptero-tile__header'}>
                            <div className={'ptero-toolbar'} style={{ marginTop: 0, justifyContent: 'space-between' }}>
                                <div>
                                    <h3 className={'ptero-page-title ptero-stack ptero-stack--row'}>
                                        {schedule.name}
                                        {schedule.isProcessing ? (
                                            <span className={'cds--tag cds--tag--gray cds--tag--sm'}>
                                                <Spinner size={'small'} />
                                                Processing
                                            </span>
                                        ) : (
                                            <ActivePill active={schedule.isActive} />
                                        )}
                                    </h3>
                                    <p className={'ptero-muted'}>
                                        Last run at:&nbsp;
                                        {schedule.lastRunAt ? format(schedule.lastRunAt, "MMM do 'at' h:mma") : 'n/a'}
                                        <span style={{ marginInlineStart: '1rem' }}>
                                            Next run at:&nbsp;
                                            {schedule.nextRunAt ? format(schedule.nextRunAt, "MMM do 'at' h:mma") : 'n/a'}
                                        </span>
                                    </p>
                                </div>
                                <Can action={'schedule.update'}>
                                    <div className={'ptero-stack ptero-stack--row'}>
                                        <Button.Text onClick={toggleEditModal}>Edit</Button.Text>
                                        <NewTaskButton schedule={schedule} />
                                    </div>
                                </Can>
                            </div>
                        </div>
                        <div className={'ptero-cron-grid'}>
                            <CronBox title={'Minute'} value={schedule.cron.minute} />
                            <CronBox title={'Hour'} value={schedule.cron.hour} />
                            <CronBox title={'Day (Month)'} value={schedule.cron.dayOfMonth} />
                            <CronBox title={'Month'} value={schedule.cron.month} />
                            <CronBox title={'Day (Week)'} value={schedule.cron.dayOfWeek} />
                        </div>
                        <div className={'ptero-tile__body'}>
                            {schedule.tasks.length > 0
                                ? schedule.tasks
                                      .sort((a, b) =>
                                          a.sequenceId === b.sequenceId ? 0 : a.sequenceId > b.sequenceId ? 1 : -1
                                      )
                                      .map((task) => (
                                          <ScheduleTaskRow
                                              key={`${schedule.id}_${task.id}`}
                                              task={task}
                                              schedule={schedule}
                                          />
                                      ))
                                : null}
                        </div>
                    </div>
                    <EditScheduleModal visible={showEditModal} schedule={schedule} onModalDismissed={toggleEditModal} />
                    <div className={'ptero-toolbar'}>
                        <Can action={'schedule.delete'}>
                            <DeleteScheduleButton
                                scheduleId={schedule.id}
                                onDeleted={() => history.push(`/server/${id}/schedules`)}
                            />
                        </Can>
                        {schedule.tasks.length > 0 && (
                            <Can action={'schedule.update'}>
                                <RunScheduleButton schedule={schedule} />
                            </Can>
                        )}
                    </div>
                </>
            )}
        </PageContentBlock>
    );
};
