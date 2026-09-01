import React from 'react';
import { PaginationDataSet } from '@/api/http';
import classNames from 'classnames';
import { PaginationNav } from '@carbon/react';

interface Props {
    className?: string;
    pagination: PaginationDataSet;
    onPageSelect: (page: number) => void;
}

const PaginationFooter = ({ pagination, className, onPageSelect }: Props) => {
    const start = (pagination.currentPage - 1) * pagination.perPage;
    const end = (pagination.currentPage - 1) * pagination.perPage + pagination.count;

    if (pagination.total === 0) {
        return null;
    }

    return (
        <div className={classNames('flex items-center justify-between my-2', className)}>
            <p className={'cds--label'}>
                Showing {Math.max(start, Math.min(pagination.total, 1))} to {end} of {pagination.total} results.
            </p>
            {pagination.totalPages > 1 && (
                <PaginationNav
                    itemsShown={Math.min(5, pagination.totalPages)}
                    totalItems={pagination.totalPages}
                    page={pagination.currentPage - 1}
                    onChange={(index: number) => onPageSelect(index + 1)}
                />
            )}
        </div>
    );
};

export default PaginationFooter;
