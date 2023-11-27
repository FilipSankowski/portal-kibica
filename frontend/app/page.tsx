'use client'

import InputLabeled from "@/components/InputLabeled";
import { Dispatch, SetStateAction, useEffect, useState } from "react"

type Leagues = Array<{
  league: {
    id: number
    name: string
    type: string
    logo: string
  }
  country: {
    name: string
    code: any
    flag: any
  }
  seasons: Array<{
    year: number
    start: string
    end: string
    current: boolean
    coverage: {
      fixtures: {
        events: boolean
        lineups: boolean
        statistics_fixtures: boolean
        statistics_players: boolean
      }
      standings: boolean
      players: boolean
      top_scorers: boolean
      top_assists: boolean
      top_cards: boolean
      injuries: boolean
      predictions: boolean
      odds: boolean
    }
  }>
}>

export default function Football() {
  const [leagueList, setLeagueList] : [leagueList: Leagues, setLeagueList: Dispatch<SetStateAction<Leagues>>] = useState([]);
  const [leagueId, setLeagueId] = useState('');
  const [season, setSeason] = useState('2023');
  const [leagueLimit, setLeagueLimit] = useState(10);

  const leagueOptions = leagueList.map((league) => {
    return {
      name: league.league.name,
      value: league,
    }
  })

  useEffect(() => {
    fetch(`${process.env.NEXT_PUBLIC_BACKEND_URL}/api/leagues`)
      .then(res => res.json())
      .then(res => setLeagueList(res))
      .catch(res => console.error('Leagues fetch error'))
  }, [])

  return (
    <div className="flex grow text-gray-100">
      <div className="w-1/6 bg-gray-950 grow">
        t
      </div>
      <div className="flex flex-column flex-wrap gap-5 p-2 w-5/6 bg-gray-900 grow">
        <div className="w-full p-3 mx-3 text-center border-b border-gray-800">
          <InputLabeled 
            type="text" 
            value={leagueId}
            onChange={e => setLeagueId(e.target.value)}
            className=""
          >
            <span className="mr-5 font-bold">Wybierz ligę:</span> 
          </InputLabeled>
          <InputLabeled 
            type="text" 
            value={season}
            onChange={e => setSeason(e.target.value)}
            className=""
          >
            <span className="mx-5 font-bold">oraz sezon: </span> 
          </InputLabeled>
        </div>

        <div className="w-full flex gap-4">
          <button onClick={() => setLeagueLimit(leagueLimit + 10)} hidden={leagueList.length === 0}>Show more</button>
          <button onClick={() => setLeagueLimit(leagueList.length)} hidden={leagueList.length === 0}>Show all</button>
          <button onClick={() => setLeagueLimit(10)} hidden={leagueList.length === 0}>Reset</button>
        </div>

        <div className="w-full">
          {leagueList.map((league, index) => {
            if (index < leagueLimit) {
              return (
                <p key={index}>{league.league.name}</p>
              )
            }
          })}
        </div>

        <div className="w-full flex gap-4">
          <button onClick={() => setLeagueLimit(leagueLimit + 10)} hidden={leagueList.length === 0}>Show more</button>
          <button onClick={() => setLeagueLimit(leagueList.length)} hidden={leagueList.length === 0}>Show all</button>
          <button onClick={() => setLeagueLimit(10)} hidden={leagueList.length === 0}>Reset</button>
        </div>
        
      </div>
    </div>
  )
}
