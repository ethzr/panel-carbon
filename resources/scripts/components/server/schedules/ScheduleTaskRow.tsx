import React, { useState } from 'react';
import { Schedule, Task } from '@/api/server/schedules/getServerSchedules';
import { FontAwesomeIcon } from '@fortawesome/react-fontawesome';
import {
    faArrowCircleDown,
    faClock,
    faCode,
    faFileArchive,
    faPencilAlt,
    faToggleOn,
    faTrashAlt,
} from '@fortawesome/free-solid-svg-icons';
import deleteScheduleTask from '@/api/server/schedules/deleteScheduleTask';
import { httpErrorToHuman } from '@/api/http';
import SpinnerOverlay from '@/components/elements/SpinnerOverlay';
import TaskDetailsModal from '@/components/server/schedules/TaskDetailsModal';
import Can from '@/components/elements/Can';
import useFlash from '@/plugins/useFlash';
import { ServerContext } from '@/state/server';
import ConfirmationModal from '@/components/elements/ConfirmationModal';
import Icon from '@/components/elements/Icon';
import { Button } from '@carbon/react';

interface Props {
    schedule: Schedule;
    task: Task;
}

const getActionDetails = (action: string): [string, any] => {
    switch (action) {
        case 'command':
            return ['Send Command', faCode];
        case 'power':
            return ['Send Power Action', faToggleOn];
        case 'backup':
            return ['Create Backup', faFileArchive];
        default:
            return ['Unknown Action', faCode];
    }
};

export default ({ schedule, task }: Props) => {
    const uuid = ServerContext.useStoreState((state) => state.server.data!.uuid);
    const { clearFlashes, addError } = useFlash();
    const [visible, setVisible] = useState(false);
    const [isLoading, setIsLoading] = useState(false);
    const [isEditing, setIsEditing] = useState(false);
    const appendSchedule = ServerContext.useStoreActions((actions) => actions.schedules.appendSchedule);

    const onConfirmDeletion = () => {
        setIsLoading(true);
        clearFlashes('schedules');
        deleteScheduleTask(uuid, schedule.id, task.id)
            .then(() =>
                appendSchedule({
                    ...schedule,
                    tasks: schedule.tasks.filter((t) => t.id !== task.id),
                })
            )
            .catch((error) => {
                console.error(error);
                setIsLoading(false);
                addError({ message: httpErrorToHuman(error), key: 'schedules' });
            });
    };

    const [title, icon] = getActionDetails(task.action);

    return (
        <div className={'ptero-task-row'}>
            <SpinnerOverlay visible={isLoading} fixed size={'large'} />
            <TaskDetailsModal
                schedule={schedule}
                task={task}
                visible={isEditing}
                onModalDismissed={() => setIsEditing(false)}
            />
            <ConfirmationModal
                title={'Confirm task deletion'}
                buttonText={'Delete Task'}
                onConfirmed={onConfirmDeletion}
                visible={visible}
                onModalDismissed={() => setVisible(false)}
            >
                Are you sure you want to delete this task? This action cannot be undone.
            </ConfirmationModal>
            <div className={'ptero-resource-row__icon'}>
                <FontAwesomeIcon icon={icon} />
            </div>
            <div className={'ptero-resource-row__body'}>
                <p>{title}</p>
                {task.payload && (
                    <div>
                        {task.action === 'backup' && (
                            <p className={'ptero-resource-row__label'}>Ignoring files & folders:</p>
                        )}
                        <div className={'ptero-code'}>{task.payload}</div>
                    </div>
                )}
            </div>
            <div className={'ptero-resource-row__actions'}>
                {task.continueOnFailure && (
                    <span className={'cds--tag cds--tag--warm-gray cds--tag--sm'}>
                        <Icon icon={faArrowCircleDown} className={'ptero-icon'} />
                        Continues on Failure
                    </span>
                )}
                {task.sequenceId > 1 && task.timeOffset > 0 && (
                    <span className={'cds--tag cds--tag--gray cds--tag--sm'}>
                        <Icon icon={faClock} className={'ptero-icon'} />
                        {task.timeOffset}s later
                    </span>
                )}
                <Can action={'schedule.update'}>
                    <Button
                        kind={'ghost'}
                        size={'sm'}
                        hasIconOnly
                        iconDescription={'Edit scheduled task'}
                        type={'button'}
                        onClick={() => setIsEditing(true)}
                    >
                        <FontAwesomeIcon icon={faPencilAlt} />
                    </Button>
                </Can>
                <Can action={'schedule.update'}>
                    <Button
                        kind={'ghost'}
                        size={'sm'}
                        hasIconOnly
                        iconDescription={'Delete scheduled task'}
                        type={'button'}
                        onClick={() => setVisible(true)}
                    >
                        <FontAwesomeIcon icon={faTrashAlt} />
                    </Button>
                </Can>
            </div>
        </div>
    );
};
