type SelectProps = {
    children?: React.ReactNode,
    value: string,
    id?: string,
    className?: string,
    name?: string,
}

export default function Select({children, className, ...props} : SelectProps) {
    return (
      <select
      className={`${className} p-1 bg-gray-800 border-gray-950 border-2 outline-none hover:border-black focus:border-black`}
      {...props}
      >
        {children}
      </select>
    )
  }