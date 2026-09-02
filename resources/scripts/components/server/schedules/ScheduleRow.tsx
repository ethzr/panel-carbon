import React from 'react';
import { Schedule } from '@/api/server/schedules/getServerSchedules';
import { FontAwesomeIcon } from '@fortawesome/react-fontawesome';
import { faCalendarAlt } from '@fortawesome/free-solid-svg-icons';
import { format } from 'date-fns';
import ScheduleCronRow from '@/components/server/schedules/ScheduleCronRow';

export default ({ schedule }: { schedule: Schedule }) => (
    <>
        <div className={'ptero-resource-row__icon'}>
            <FontAwesomeIcon icon={faCalendarAlt} fixedWidth />
        </div>
        <div className={'ptero-resource-row__body'}>
            <p>{schedule.name}</p>
            <p className={'ptero-muted'}>
                Last run at: {schedule.lastRunAt ? format(schedule.lastRunAt, "MMM do 'at' h:mma") : 'never'}
            </p>
        </div>
        <ScheduleCronRow cron={schedule.cron} />
        <span
            className={`cds--tag cds--tag--sm ${
                schedule.isProcessing ? 'cds--tag--gray' : schedule.isActive ? 'cds--tag--green' : 'cds--tag--gray'
            }`}
        >
            {schedule.isProcessing ? 'Processing' : schedule.isActive ? 'Active' : 'Inactive'}
        </span>
    </>
);
