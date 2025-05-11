import React, { useState, useEffect } from 'react';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout';
import { Head } from '@inertiajs/react';
import Form from './Form';
import { Inertia } from '@inertiajs/inertia';
const Index = ({ boards = [] }) => {
  const [open, setOpen] = useState(false);
  const [openEdit, setOpenEdit] = useState(false);
  const [board, setBoard] = useState(boards||null);
  const [search, setSearch] = useState('');
  const [boardsSearch, setBoardsSearch] = useState([]);

  useEffect(()=>{
    if(search.length > 2 && boardsSearch.length !== 0){
      console.log('search', search);
      updateBoard(boardsSearch);
    }
  }, [search]);

  const updateBoard = (board)=>{
    console.log('esta actualizando cosas')
    setBoard(board)
  }

  const openForm = (type, board)=>{
    setOpen(true);
    setBoard(board);
    if(type === 'edit'){
      setOpenEdit(true);
    }
  }
  const searchBoard = (search)=>{
    console.log('search', search);
    setSearch(search);
    let url = search != '' && search.length > 2 ? 'boards.search' : 'boards';
    axios.get(route(url), {
      params: {
        search: search
      }
    }).then(res=>{
      let data = res.data.data || [];
      setBoardsSearch(data);
    }).catch(err=>{
      console.error(err);
    });
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
          {console.log(board)}
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
      {board.length === 0 ? (
        <div className="text-gray-500">No boards found.</div>
      ) : (
        <>
        <div className="mb-6 flex justify-center">
          <input type="text" className='border border-gray-300 rounded-md p-2 w-1/2' placeholder='Search boards' onChange={(e)=>searchBoard(e.target.value)} />
        </div>
        <div className="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-6">
          {board.map((board) => (
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
