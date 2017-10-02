function logM( t ){
    try{
        console.log( t );
    }catch (err){}
}

function isMobile(){
    return ( /Android|webOS|iPhone|iPad|iPod|BlackBerry|IEMobile|Opera Mini/i.test(navigator.userAgent) );
}