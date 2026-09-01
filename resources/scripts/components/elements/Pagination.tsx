import React from 'react';
import { PaginatedResult } from '@/api/http';
import { PaginationNav } from '@carbon/react';

interface RenderFuncProps<T> {
    items: T[];
    isLastPage: boolean;
    isFirstPage: boolean;
}

interface Props<T> {
    data: PaginatedResult<T>;
    showGoToLast?: boolean;
    showGoToFirst?: boolean;
    onPageSelect: (page: number) => void;
    children: (props: RenderFuncProps<T>) => React.ReactNode;
}

function Pagination<T>({ data: { items, pagination }, onPageSelect, children }: Props<T>) {
    const isFirstPage = pagination.currentPage === 1;
    const isLastPage = pagination.currentPage >= pagination.totalPages;

    return (
        <>
            {children({ items, isFirstPage, isLastPage })}
            {pagination.totalPages > 1 && (
                <div style={{ marginTop: '1rem', display: 'flex', justifyContent: 'center' }}>
                    <PaginationNav
                        itemsShown={Math.min(5, pagination.totalPages)}
                        totalItems={pagination.totalPages}
                        page={pagination.currentPage - 1}
                        onChange={(index: number) => onPageSelect(index + 1)}
                    />
                </div>
            )}
        </>
    );
}

export default Pagination;
