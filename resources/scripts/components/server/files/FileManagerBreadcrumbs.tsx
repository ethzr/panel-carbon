import React, { useEffect, useState } from 'react';
import { ServerContext } from '@/state/server';
import { NavLink, useLocation } from 'react-router-dom';
import { encodePathSegments, hashToPath } from '@/helpers';
import { Breadcrumb, BreadcrumbItem } from '@carbon/react';

interface Props {
    renderLeft?: JSX.Element;
    withinFileEditor?: boolean;
    isNewFile?: boolean;
}

export default ({ renderLeft, withinFileEditor, isNewFile }: Props) => {
    const [file, setFile] = useState<string | null>(null);
    const id = ServerContext.useStoreState((state) => state.server.data!.id);
    const directory = ServerContext.useStoreState((state) => state.files.directory);
    const { hash } = useLocation();

    useEffect(() => {
        const path = hashToPath(hash);

        if (withinFileEditor && !isNewFile) {
            const name = path.split('/').pop() || null;
            setFile(name);
        }
    }, [withinFileEditor, isNewFile, hash]);

    const breadcrumbs = (): { name: string; path?: string }[] =>
        directory
            .split('/')
            .filter((directory) => !!directory)
            .map((directory, index, dirs) => {
                if (!withinFileEditor && index === dirs.length - 1) {
                    return { name: directory };
                }

                return { name: directory, path: `/${dirs.slice(0, index + 1).join('/')}` };
            });

    return (
        <div style={{ display: 'flex', alignItems: 'center', gap: '0.75rem', minWidth: 0, flex: 1 }}>
            {renderLeft}
            <Breadcrumb noTrailingSlash>
                <BreadcrumbItem>
                    <NavLink to={`/server/${id}/files`}>container</NavLink>
                </BreadcrumbItem>
                {breadcrumbs().map((crumb, index) =>
                    crumb.path ? (
                        <BreadcrumbItem key={index}>
                            <NavLink to={`/server/${id}/files#${encodePathSegments(crumb.path)}`}>{crumb.name}</NavLink>
                        </BreadcrumbItem>
                    ) : (
                        <BreadcrumbItem key={index} isCurrentPage>
                            {crumb.name}
                        </BreadcrumbItem>
                    )
                )}
                {file && (
                    <BreadcrumbItem isCurrentPage>{file}</BreadcrumbItem>
                )}
            </Breadcrumb>
        </div>
    );
};
