import React from 'react';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout';
import { Head } from '@inertiajs/react';
const Show = ({ board }) => {
    return (
        <AuthenticatedLayout
            header={
                <h2 className="text-xl font-semibold leading-tight text-gray-800">
                    {board.name}
                </h2>
            }
        >
          <Head title="Boards" />
            <div className="p-6">
                <h1 className="text-2xl font-bold mb-6">{board.name}</h1>
                <p className="text-gray-600">{board.description || 'No description'}</p>
            </div>
        </AuthenticatedLayout>
    );
};

export default Show;