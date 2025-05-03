import React from 'react';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout';
import { Head } from '@inertiajs/react';
import Form from './Form';
import { Inertia } from '@inertiajs/inertia';
const Index = ({ boards = [] }) => {
  const [open, setOpen] = React.useState(false);
  const [openEdit, setOpenEdit] = React.useState(false);
  const [board, setBoard] = React.useState(null);
  const openForm = (type, board)=>{
    setOpen(true);
    setBoard(board);
    if(type === 'edit'){
      setOpenEdit(true);
    }
  }
  const closeForm = ()=>{
    setOpen(false);
    setOpenEdit(false);
    setBoard(null);
  }
  const visit = (id)=>{
    Inertia.visit(`/boards/${id}`);
  }
  const deleteBoard = (id)=>{
    Inertia.delete(`/boards/${id}`);
  }
    return (
        <AuthenticatedLayout
            header={
                <h2 className="text-xl font-semibold leading-tight text-gray-800">
                    Boards <span className="text-gray-400 cursor-pointer" onClick={()=>openForm('create', null)}>Create new board</span>
                </h2>
            }
        >
          <Head title="Boards" />
          {
            open && openEdit != true ? <Form 
              edit={false} 
              open={open} 
              closeForm={closeForm} 
              board={board}
            /> : <Form 
              edit={true} 
              open={open} 
              closeForm={closeForm} 
              board={board}
            />
          }
            <div className="p-6">
                <h1 className="text-2xl font-bold mb-6">Boards</h1>
      {boards.length === 0 ? (
        <div className="text-gray-500">No boards found.</div>
      ) : (
        <>
        <div className="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-6">
          {boards.map((board) => (
            <div onDoubleClick={()=>visit(board.id)}
              key={board.id}
              className="cursor-pointer bg-white shadow-md rounded-2xl p-4 hover:shadow-lg transition duration-200"
            >
              <h2 className="text-xl font-semibold mb-2">{board.name}</h2>
              <p className="text-gray-600">{board.description || 'No description'}</p>
              <div className="mt-4 text-sm text-gray-400">
                Created at: {new Date(board.created_at).toLocaleDateString()}
              </div>
              <div className="mt-4">
                <span 
                  onClick={()=>openForm('edit', board)}
                  className="inline-flex items-center px-4 py-2 text-base font-semibold leading-6 text-white transition duration-150 ease-in-out bg-blue-600 border border-transparent rounded-md hover:bg-blue-500 focus:outline-none focus:border-blue-700 focus:shadow-outline-blue active:bg-blue-700"
                >
                  Edit
                </span>
                <span 
                  onClick={()=>deleteBoard(board.id)}
                  className="inline-flex items-center px-4 py-2 text-base font-semibold leading-6 text-white transition duration-150 ease-in-out bg-red-600 border border-transparent rounded-md hover:bg-red-500 focus:outline-none focus:border-red-700 focus:shadow-outline-red active:bg-red-700"
                >
                  Delete
                </span>
              </div>
            </div>
          ))}
        </div>
        </>
      )}
    </div>
    
    </AuthenticatedLayout>
  );
};

export default Index;
