import Link from "next/link";

export default function Header() {
  return (
    <div className="w-full bg-gray-950 text-white p-4 border-b border-gray-900">
      <Link
      href="/"
      className="w-full h-full"
      >
        <span className="py-4 px-2">Portal Kibica</span>
      </Link>
    </div>
    
  )
}