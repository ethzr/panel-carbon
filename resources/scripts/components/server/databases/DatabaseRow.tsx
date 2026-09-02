import React, { useState } from 'react';
import { FontAwesomeIcon } from '@fortawesome/react-fontawesome';
import { faDatabase, faEye, faTrashAlt } from '@fortawesome/free-solid-svg-icons';
import Modal from '@/components/elements/Modal';
import { Form, Formik, FormikHelpers } from 'formik';
import Field from '@/components/elements/Field';
import { object, string } from 'yup';
import FlashMessageRender from '@/components/FlashMessageRender';
import { ServerContext } from '@/state/server';
import deleteServerDatabase from '@/api/server/databases/deleteServerDatabase';
import { httpErrorToHuman } from '@/api/http';
import RotatePasswordButton from '@/components/server/databases/RotatePasswordButton';
import Can from '@/components/elements/Can';
import { ServerDatabase } from '@/api/server/databases/getServerDatabases';
import useFlash from '@/plugins/useFlash';
import Button from '@/components/elements/Button';
import Label from '@/components/elements/Label';
import Input from '@/components/elements/Input';
import GreyRowBox from '@/components/elements/GreyRowBox';
import CopyOnClick from '@/components/elements/CopyOnClick';

interface Props {
    database: ServerDatabase;
    className?: string;
}

export default ({ database, className }: Props) => {
    const uuid = ServerContext.useStoreState((state) => state.server.data!.uuid);
    const { addError, clearFlashes } = useFlash();
    const [visible, setVisible] = useState(false);
    const [connectionVisible, setConnectionVisible] = useState(false);

    const appendDatabase = ServerContext.useStoreActions((actions) => actions.databases.appendDatabase);
    const removeDatabase = ServerContext.useStoreActions((actions) => actions.databases.removeDatabase);

    const jdbcConnectionString = `jdbc:mysql://${database.username}${
        database.password ? `:${encodeURIComponent(database.password)}` : ''
    }@${database.connectionString}/${database.name}`;

    const schema = object().shape({
        confirm: string()
            .required('The database name must be provided.')
            .oneOf([database.name.split('_', 2)[1], database.name], 'The database name must be provided.'),
    });

    const submit = (values: { confirm: string }, { setSubmitting }: FormikHelpers<{ confirm: string }>) => {
        clearFlashes();
        deleteServerDatabase(uuid, database.id)
            .then(() => {
                setVisible(false);
                setTimeout(() => removeDatabase(database.id), 150);
            })
            .catch((error) => {
                console.error(error);
                setSubmitting(false);
                addError({ key: 'database:delete', message: httpErrorToHuman(error) });
            });
    };

    return (
        <>
            <Formik onSubmit={submit} initialValues={{ confirm: '' }} validationSchema={schema} isInitialValid={false}>
                {({ isSubmitting, isValid, resetForm }) => (
                    <Modal
                        visible={visible}
                        dismissable={!isSubmitting}
                        showSpinnerOverlay={isSubmitting}
                        onDismissed={() => {
                            setVisible(false);
                            resetForm();
                        }}
                    >
                        <FlashMessageRender byKey={'database:delete'} className={'mb-4'} />
                        <h2>Confirm database deletion</h2>
                        <p className={'ptero-muted'}>
                            Deleting a database is a permanent action, it cannot be undone. This will permanently delete
                            the <strong>{database.name}</strong> database and remove all associated data.
                        </p>
                        <Form>
                            <Field
                                type={'text'}
                                id={'confirm_name'}
                                name={'confirm'}
                                label={'Confirm Database Name'}
                                description={'Enter the database name to confirm deletion.'}
                            />
                            <div className={'ptero-modal-actions'}>
                                <Button type={'button'} isSecondary onClick={() => setVisible(false)}>
                                    Cancel
                                </Button>
                                <Button type={'submit'} color={'red'} disabled={!isValid}>
                                    Delete Database
                                </Button>
                            </div>
                        </Form>
                    </Modal>
                )}
            </Formik>
            <Modal visible={connectionVisible} onDismissed={() => setConnectionVisible(false)}>
                <FlashMessageRender byKey={'database-connection-modal'} className={'mb-4'} />
                <h3>Database connection details</h3>
                <div className={'ptero-stack'}>
                    <div>
                        <Label>Endpoint</Label>
                        <CopyOnClick text={database.connectionString}>
                            <Input type={'text'} readOnly value={database.connectionString} />
                        </CopyOnClick>
                    </div>
                    <div>
                        <Label>Connections from</Label>
                        <Input type={'text'} readOnly value={database.allowConnectionsFrom} />
                    </div>
                    <div>
                        <Label>Username</Label>
                        <CopyOnClick text={database.username}>
                            <Input type={'text'} readOnly value={database.username} />
                        </CopyOnClick>
                    </div>
                    <Can action={'database.view_password'}>
                        <div>
                            <Label>Password</Label>
                            <CopyOnClick text={database.password} showInNotification={false}>
                                <Input type={'text'} readOnly value={database.password} />
                            </CopyOnClick>
                        </div>
                    </Can>
                    <div>
                        <Label>JDBC Connection String</Label>
                        <CopyOnClick text={jdbcConnectionString} showInNotification={false}>
                            <Input type={'text'} readOnly value={jdbcConnectionString} />
                        </CopyOnClick>
                    </div>
                </div>
                <div className={'ptero-modal-actions'}>
                    <Can action={'database.update'}>
                        <RotatePasswordButton databaseId={database.id} onUpdate={appendDatabase} />
                    </Can>
                    <Button isSecondary onClick={() => setConnectionVisible(false)}>
                        Close
                    </Button>
                </div>
            </Modal>
            <GreyRowBox $hoverable={false} className={className}>
                <div className={'ptero-resource-row__icon'}>
                    <FontAwesomeIcon icon={faDatabase} fixedWidth />
                </div>
                <div className={'ptero-resource-row__body'}>
                    <CopyOnClick text={database.name}>
                        <p className={'cds--productive-heading-01'}>{database.name}</p>
                    </CopyOnClick>
                </div>
                <div className={'ptero-resource-row__meta'}>
                    <CopyOnClick text={database.connectionString}>
                        <p>{database.connectionString}</p>
                    </CopyOnClick>
                    <span className={'ptero-resource-row__label'}>Endpoint</span>
                </div>
                <div className={'ptero-resource-row__meta'}>
                    <p>{database.allowConnectionsFrom}</p>
                    <span className={'ptero-resource-row__label'}>Connections from</span>
                </div>
                <div className={'ptero-resource-row__meta'}>
                    <CopyOnClick text={database.username}>
                        <p>{database.username}</p>
                    </CopyOnClick>
                    <span className={'ptero-resource-row__label'}>Username</span>
                </div>
                <div className={'ptero-resource-row__actions'}>
                    <Button isSecondary onClick={() => setConnectionVisible(true)}>
                        <FontAwesomeIcon icon={faEye} fixedWidth />
                    </Button>
                    <Can action={'database.delete'}>
                        <Button color={'red'} isSecondary onClick={() => setVisible(true)}>
                            <FontAwesomeIcon icon={faTrashAlt} fixedWidth />
                        </Button>
                    </Can>
                </div>
            </GreyRowBox>
        </>
    );
};
