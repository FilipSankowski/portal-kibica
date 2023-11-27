type InputProps = {
    children?: React.ReactNode,
    type: string,
    value: string,
    id?: string,
    className?: string,
    name?: string,
    onChange?: React.ChangeEventHandler<HTMLInputElement>,
}

export default function InputLabeled({children, className = '', ...props} : InputProps) {
    return (
        <>
            {children}
            <input 
            className={`${className} p-1 bg-gray-800 border-gray-950 border-2 outline-none hover:border-black focus:border-black`}
            {...props}
            />
        </>
    )
}