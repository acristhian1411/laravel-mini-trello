import React, { useEffect } from 'react';
import { useForm } from '@inertiajs/react';
export default function Form({open, closeForm, edit, board}) {
    const { data, setData, post, put, errors } = useForm({
        name: '',
        description: '',
    }); 
    useEffect(() => {
        if(edit && board){
            setData(board);
        }else{
            setData({
                name: '',
                description: '',
            });
        }
    }, [edit, board]);
    const handleSubmit = (e) => {
        e.preventDefault();
        if(edit){
            put(`/boards/${board.id}`, {
                onSuccess: () => {
                    setData({
                        name: '',
                        description: '',
                    });
                    closeForm();
                },
            });
        }else{
            post('/boards', {
                onSuccess: () => {
                    setData({
                        name: '',
                        description: '',
                    });
                    closeForm();
                },
            });
        }
    };
    return (
        <div>
            <dialog
                open={open}
                className="bg-white rounded-md shadow-md p-4 max-w-md mx-auto fixed inset-0 z-50"
                style={{ top: '50%', left: '50%', transform: 'translate(-50%, -50%)' }}
            >
                <button
                    onClick={closeForm}
                    style={{position: 'absolute', top: 0, right: 0}}
                    className="bg-transparent hover:bg-gray-500 text-gray-700 font-semibold hover:text-white py-2 px-4 border border-gray-500 hover:border-transparent rounded"
                >
                    X
                </button>
                <h1 className="text-2xl font-bold mb-4">{edit ? 'Edit' : 'Create'} Board</h1>
                <form onSubmit={handleSubmit} className="flex flex-col space-y-4 mt-4">
                    <input type="text" name="name" className="border border-gray-500 rounded-md p-2" value={data.name} required onChange={(e) => setData('name', e.target.value)} />
                    <input type="text" name="description" className="border border-gray-500 rounded-md p-2" value={data.description} required onChange={(e) => setData('description', e.target.value)} />
                    <button type="submit" disabled={post.processing || put.processing} className="bg-blue-500 text-white px-4 py-2 rounded-md">
                        {post.processing ? 'Loading...' : edit ? 'Update' : 'Create'}
                    </button>  
                    <button type="reset" className="bg-gray-500 text-white px-4 py-2 rounded-md">Cancelar</button> 
                </form>
                {errors.name && <div className="text-red-500">{errors.name}</div>}
                {errors.description && <div className='text-red-500'>{errors.description}</div>}
            </dialog>
        </div>
    );
}